import express from 'express';
import { Equipment } from '../database/schema/equipment';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List equipment for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const equipment = await Equipment.find({ tenantId });
  res.json(equipment);
});

// Get equipment by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const item = await Equipment.findOne({ _id: req.params.id, tenantId });
  if (!item) return res.status(404).json({ error: 'Not found' });
  res.json(item);
});

// Create equipment
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const item = await Equipment.create({ ...req.body, tenantId });
    res.status(201).json(item);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update equipment
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const item = await Equipment.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!item) return res.status(404).json({ error: 'Not found' });
  res.json(item);
});

// Delete equipment (soft delete not required here, but can be added)
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const item = await Equipment.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!item) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const equipmentRoutes = router;
