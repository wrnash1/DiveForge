import express from 'express';
import { DiveSite } from '../database/schema/diveSite';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List dive sites for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const sites = await DiveSite.find({ tenantId });
  res.json(sites);
});

// Get dive site by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const site = await DiveSite.findOne({ _id: req.params.id, tenantId });
  if (!site) return res.status(404).json({ error: 'Not found' });
  res.json(site);
});

// Create dive site
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const site = await DiveSite.create({ ...req.body, tenantId });
    res.status(201).json(site);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update dive site
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const site = await DiveSite.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!site) return res.status(404).json({ error: 'Not found' });
  res.json(site);
});

// Delete dive site
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const site = await DiveSite.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!site) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const diveSiteRoutes = router;
