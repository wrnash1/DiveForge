import express from 'express';
import { DiveLog } from '../database/schema/diveLog';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List dive logs for customer or tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { customerId } = req.query;
  const query: any = { tenantId };
  if (customerId) query.customerId = customerId;
  const logs = await DiveLog.find(query).sort({ date: -1 });
  res.json(logs);
});

// Get dive log by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const log = await DiveLog.findOne({ _id: req.params.id, tenantId });
  if (!log) return res.status(404).json({ error: 'Not found' });
  res.json(log);
});

// Create dive log
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const log = await DiveLog.create({ ...req.body, tenantId });
    res.status(201).json(log);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update dive log
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const log = await DiveLog.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!log) return res.status(404).json({ error: 'Not found' });
  res.json(log);
});

// Delete dive log
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const log = await DiveLog.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!log) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const diveLogRoutes = router;
