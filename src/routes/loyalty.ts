import express from 'express';
import { Loyalty } from '../database/schema/loyalty';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List loyalty records for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const loyalty = await Loyalty.find({ tenantId });
  res.json(loyalty);
});

// Get loyalty record by customer ID
router.get('/customer/:customerId', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const loyalty = await Loyalty.findOne({ tenantId, customerId: req.params.customerId });
  if (!loyalty) return res.status(404).json({ error: 'Not found' });
  res.json(loyalty);
});

// Create or update loyalty record for a customer
router.post('/customer/:customerId', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { points, tier, history } = req.body;
  const loyalty = await Loyalty.findOneAndUpdate(
    { tenantId, customerId: req.params.customerId },
    { $set: { points, tier }, $push: { history: { $each: history || [] } } },
    { upsert: true, new: true }
  );
  res.json(loyalty);
});

// Add points to a customer
router.post('/customer/:customerId/earn', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { amount, description } = req.body;
  const loyalty = await Loyalty.findOneAndUpdate(
    { tenantId, customerId: req.params.customerId },
    {
      $inc: { points: amount },
      $push: { history: { date: new Date(), type: 'earn', amount, description } }
    },
    { upsert: true, new: true }
  );
  res.json(loyalty);
});

// Redeem points for a customer
router.post('/customer/:customerId/redeem', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { amount, description } = req.body;
  const loyalty = await Loyalty.findOneAndUpdate(
    { tenantId, customerId: req.params.customerId, points: { $gte: amount } },
    {
      $inc: { points: -amount },
      $push: { history: { date: new Date(), type: 'redeem', amount: -amount, description } }
    },
    { new: true }
  );
  if (!loyalty) return res.status(400).json({ error: 'Insufficient points or not found' });
  res.json(loyalty);
});

export const loyaltyRoutes = router;
