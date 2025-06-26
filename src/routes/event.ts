import express from 'express';
import { Event } from '../database/schema/event';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List events for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const events = await Event.find({ tenantId });
  res.json(events);
});

// Get event by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const event = await Event.findOne({ _id: req.params.id, tenantId });
  if (!event) return res.status(404).json({ error: 'Not found' });
  res.json(event);
});

// Create event
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const event = await Event.create({ ...req.body, tenantId });
    res.status(201).json(event);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update event
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const event = await Event.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!event) return res.status(404).json({ error: 'Not found' });
  res.json(event);
});

// Delete event
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const event = await Event.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!event) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const eventRoutes = router;
