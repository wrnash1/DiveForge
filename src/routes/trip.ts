import express from 'express';
import { Trip } from '../database/schema/trip';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List trips for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const trips = await Trip.find({ tenantId });
  res.json(trips);
});

// Get trip by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const trip = await Trip.findOne({ _id: req.params.id, tenantId });
  if (!trip) return res.status(404).json({ error: 'Not found' });
  res.json(trip);
});

// Create trip
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const trip = await Trip.create({ ...req.body, tenantId });
    res.status(201).json(trip);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update trip
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const trip = await Trip.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!trip) return res.status(404).json({ error: 'Not found' });
  res.json(trip);
});

// Delete trip
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const trip = await Trip.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!trip) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const tripRoutes = router;
