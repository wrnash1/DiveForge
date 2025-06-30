import express from 'express';
import { Boat } from '../database/schema/boat';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List boats for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const boats = await Boat.find({ tenantId });
  res.json(boats);
});

// Get boat by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const boat = await Boat.findOne({ _id: req.params.id, tenantId });
  if (!boat) return res.status(404).json({ error: 'Not found' });
  res.json(boat);
});

// Create boat
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const boat = await Boat.create({ ...req.body, tenantId });
    res.status(201).json(boat);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update boat
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const boat = await Boat.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!boat) return res.status(404).json({ error: 'Not found' });
  res.json(boat);
});

// Delete boat
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const boat = await Boat.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!boat) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const boatRoutes = router;
