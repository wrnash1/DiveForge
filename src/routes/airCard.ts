import express from 'express';
import { AirCard } from '../database/schema/airCard';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List air cards for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const cards = await AirCard.find({ tenantId });
  res.json(cards);
});

// Get air card by code
router.get('/code/:code', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const card = await AirCard.findOne({ tenantId, code: req.params.code });
  if (!card) return res.status(404).json({ error: 'Not found' });
  res.json(card);
});

// Create air card
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const card = await AirCard.create({
      ...req.body,
      tenantId,
      remainingFills: req.body.totalFills,
      issuedAt: new Date()
    });
    res.status(201).json(card);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Record a fill (regular or nitrox)
router.post('/fill/:code', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { nitrox, location, notes } = req.body;
  const card = await AirCard.findOne({ tenantId, code: req.params.code, status: 'active', remainingFills: { $gt: 0 } });
  if (!card) return res.status(400).json({ error: 'Card not found or no fills left' });
  if (nitrox && !card.nitroxEligible) return res.status(400).json({ error: 'Nitrox not eligible' });
  card.remainingFills -= 1;
  card.history.push({
    date: new Date(),
    type: 'fill',
    amount: -1,
    location,
    notes: nitrox ? 'Nitrox fill' : 'Air fill'
  });
  if (card.remainingFills === 0) card.status = 'expired';
  await card.save();
  res.json(card);
});

// Update air card (e.g. adjust balance, extend expiration)
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const card = await AirCard.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!card) return res.status(404).json({ error: 'Not found' });
  res.json(card);
});

// Delete air card
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const card = await AirCard.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!card) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const airCardRoutes = router;
