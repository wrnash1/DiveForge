import express from 'express';
import { PurchaseOrder } from '../database/schema/purchaseOrder';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List purchase orders for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const orders = await PurchaseOrder.find({ tenantId });
  res.json(orders);
});

// Get purchase order by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const order = await PurchaseOrder.findOne({ _id: req.params.id, tenantId });
  if (!order) return res.status(404).json({ error: 'Not found' });
  res.json(order);
});

// Create purchase order
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const order = await PurchaseOrder.create({ ...req.body, tenantId });
    res.status(201).json(order);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update purchase order
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const order = await PurchaseOrder.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!order) return res.status(404).json({ error: 'Not found' });
  res.json(order);
});

// Delete purchase order
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const order = await PurchaseOrder.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!order) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const purchaseOrderRoutes = router;
