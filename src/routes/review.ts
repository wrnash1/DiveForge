import express from 'express';
import { Review } from '../database/schema/review';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List reviews for tenant (optionally filter by type/reference)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { type, referenceId } = req.query;
  const query: any = { tenantId };
  if (type) query.type = type;
  if (referenceId) query.referenceId = referenceId;
  const reviews = await Review.find(query).sort({ createdAt: -1 });
  res.json(reviews);
});

// Get review by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const review = await Review.findOne({ _id: req.params.id, tenantId });
  if (!review) return res.status(404).json({ error: 'Not found' });
  res.json(review);
});

// Create review
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const customerId = (req as any).user.id;
  try {
    const review = await Review.create({ ...req.body, tenantId, customerId });
    res.status(201).json(review);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update review
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const review = await Review.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!review) return res.status(404).json({ error: 'Not found' });
  res.json(review);
});

// Delete review
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const review = await Review.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!review) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const reviewRoutes = router;
