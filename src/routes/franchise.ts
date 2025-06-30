import express from 'express';
import { Franchise } from '../database/schema/franchise';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List franchises for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const franchises = await Franchise.find({ tenantId });
  res.json(franchises);
});

// Get franchise by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const franchise = await Franchise.findOne({ _id: req.params.id, tenantId });
  if (!franchise) return res.status(404).json({ error: 'Not found' });
  res.json(franchise);
});

// Create franchise
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const franchise = await Franchise.create({ ...req.body, tenantId });
    res.status(201).json(franchise);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update franchise
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const franchise = await Franchise.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!franchise) return res.status(404).json({ error: 'Not found' });
  res.json(franchise);
});

// Delete franchise
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const franchise = await Franchise.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!franchise) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const franchiseRoutes = router;
