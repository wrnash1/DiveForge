import express from 'express';
import { Staff } from '../database/schema/staff';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List staff for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const staff = await Staff.find({ tenantId });
  res.json(staff);
});

// Get staff by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const staff = await Staff.findOne({ _id: req.params.id, tenantId });
  if (!staff) return res.status(404).json({ error: 'Not found' });
  res.json(staff);
});

// Create staff
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const staff = await Staff.create({ ...req.body, tenantId });
    res.status(201).json(staff);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update staff
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const staff = await Staff.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!staff) return res.status(404).json({ error: 'Not found' });
  res.json(staff);
});

// Delete staff
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const staff = await Staff.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!staff) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const staffRoutes = router;
