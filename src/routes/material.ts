import express from 'express';
import { Material } from '../database/schema/material';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List materials for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const materials = await Material.find({ tenantId });
  res.json(materials);
});

// Get material by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const material = await Material.findOne({ _id: req.params.id, tenantId });
  if (!material) return res.status(404).json({ error: 'Not found' });
  res.json(material);
});

// Create material
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const material = await Material.create({ ...req.body, tenantId });
    res.status(201).json(material);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update material
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const material = await Material.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!material) return res.status(404).json({ error: 'Not found' });
  res.json(material);
});

// Delete material
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const material = await Material.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!material) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const materialRoutes = router;
