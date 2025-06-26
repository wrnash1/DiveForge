import express from 'express';
import { Booking } from '../database/schema/booking';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List bookings for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const bookings = await Booking.find({ tenantId });
  res.json(bookings);
});

// Get booking by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const booking = await Booking.findOne({ _id: req.params.id, tenantId });
  if (!booking) return res.status(404).json({ error: 'Not found' });
  res.json(booking);
});

// Create booking
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const booking = await Booking.create({ ...req.body, tenantId });
    res.status(201).json(booking);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update booking
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const booking = await Booking.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!booking) return res.status(404).json({ error: 'Not found' });
  res.json(booking);
});

// Delete booking
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const booking = await Booking.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!booking) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const bookingRoutes = router;
