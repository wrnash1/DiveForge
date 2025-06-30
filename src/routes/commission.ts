import express from 'express';
import { Commission } from '../database/schema/commission';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List commissions for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const commissions = await Commission.find({ tenantId });
  res.json(commissions);
});

// Get commission by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const commission = await Commission.findOne({ _id: req.params.id, tenantId });
  if (!commission) return res.status(404).json({ error: 'Not found' });
  res.json(commission);
});

// Create commission
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const commission = await Commission.create({ ...req.body, tenantId });
    res.status(201).json(commission);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update commission
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const commission = await Commission.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!commission) return res.status(404).json({ error: 'Not found' });
  res.json(commission);
});

// Delete commission
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const commission = await Commission.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!commission) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const commissionRoutes = router;
