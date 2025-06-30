import express from 'express';
import { ServiceOrder } from '../database/schema/serviceOrder';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List service orders for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const orders = await ServiceOrder.find({ tenantId });
  res.json(orders);
});

// Get service order by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const order = await ServiceOrder.findOne({ _id: req.params.id, tenantId });
  if (!order) return res.status(404).json({ error: 'Not found' });
  res.json(order);
});

// Create service order
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const order = await ServiceOrder.create({ ...req.body, tenantId });
    res.status(201).json(order);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update service order
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const order = await ServiceOrder.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!order) return res.status(404).json({ error: 'Not found' });
  res.json(order);
});

// Delete service order
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const order = await ServiceOrder.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!order) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const serviceOrderRoutes = router;
