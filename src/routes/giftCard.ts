import express from 'express';
import { GiftCard } from '../database/schema/giftCard';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List gift cards for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const cards = await GiftCard.find({ tenantId });
  res.json(cards);
});

// Get gift card by code
router.get('/code/:code', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const card = await GiftCard.findOne({ tenantId, code: req.params.code });
  if (!card) return res.status(404).json({ error: 'Not found' });
  res.json(card);
});

// Create gift card
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const card = await GiftCard.create({ ...req.body, tenantId, balance: req.body.amount, issuedAt: new Date() });
    res.status(201).json(card);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Redeem gift card
router.post('/redeem/:code', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { amount } = req.body;
  const card = await GiftCard.findOne({ tenantId, code: req.params.code, status: 'active', balance: { $gte: amount } });
  if (!card) return res.status(400).json({ error: 'Invalid or insufficient balance' });
  card.balance -= amount;
  if (card.balance === 0) card.status = 'redeemed';
  await card.save();
  res.json(card);
});

// Update gift card (e.g. mark delivered)
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const card = await GiftCard.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!card) return res.status(404).json({ error: 'Not found' });
  res.json(card);
});

// Delete gift card
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const card = await GiftCard.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!card) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const giftCardRoutes = router;
