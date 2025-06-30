import express from 'express';
import { Subscription } from '../database/schema/subscription';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List subscriptions for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const subs = await Subscription.find({ tenantId });
  res.json(subs);
});

// Get subscription by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const sub = await Subscription.findOne({ _id: req.params.id, tenantId });
  if (!sub) return res.status(404).json({ error: 'Not found' });
  res.json(sub);
});

// Create subscription
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const sub = await Subscription.create({ ...req.body, tenantId });
    res.status(201).json(sub);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update subscription
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const sub = await Subscription.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!sub) return res.status(404).json({ error: 'Not found' });
  res.json(sub);
});

// Delete subscription
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const sub = await Subscription.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!sub) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const subscriptionRoutes = router;
