import express from 'express';
import { Vendor } from '../database/schema/vendor';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List vendors for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const vendors = await Vendor.find({ tenantId });
  res.json(vendors);
});

// Get vendor by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const vendor = await Vendor.findOne({ _id: req.params.id, tenantId });
  if (!vendor) return res.status(404).json({ error: 'Not found' });
  res.json(vendor);
});

// Create vendor
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const vendor = await Vendor.create({ ...req.body, tenantId });
    res.status(201).json(vendor);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update vendor
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const vendor = await Vendor.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!vendor) return res.status(404).json({ error: 'Not found' });
  res.json(vendor);
});

// Delete vendor
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const vendor = await Vendor.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!vendor) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const vendorRoutes = router;
