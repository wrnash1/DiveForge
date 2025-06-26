import express from 'express';
import { Customer } from '../database/schema/customer';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List customers (for current tenant)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const customers = await Customer.find({ tenantId, isActive: true });
  res.json(customers);
});

// Get customer by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const customer = await Customer.findOne({ _id: req.params.id, tenantId, isActive: true });
  if (!customer) return res.status(404).json({ error: 'Not found' });
  res.json(customer);
});

// Create customer
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const customer = await Customer.create({ ...req.body, tenantId });
    res.status(201).json(customer);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update customer
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const customer = await Customer.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!customer) return res.status(404).json({ error: 'Not found' });
  res.json(customer);
});

// Soft-delete customer
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const customer = await Customer.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    { isActive: false },
    { new: true }
  );
  if (!customer) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const customerRoutes = router;
