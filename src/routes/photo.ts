import express from 'express';
import { Photo } from '../database/schema/photo';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List photos for tenant (optionally filter by type or resource)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { type, relatedResource } = req.query;
  const query: any = { tenantId };
  if (type) query.type = type;
  if (relatedResource) query.relatedResource = relatedResource;
  const photos = await Photo.find(query).sort({ createdAt: -1 });
  res.json(photos);
});

// Get photo by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const photo = await Photo.findOne({ _id: req.params.id, tenantId });
  if (!photo) return res.status(404).json({ error: 'Not found' });
  res.json(photo);
});

// Upload photo
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const uploadedBy = (req as any).user.id;
  try {
    const photo = await Photo.create({ ...req.body, tenantId, uploadedBy, 'consent.consentedAt': new Date() });
    res.status(201).json(photo);
  } catch (err) {
    res.status(400).json({ error: 'Upload failed' });
  }
});

// Update photo (e.g. description, tags, consent)
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const photo = await Photo.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!photo) return res.status(404).json({ error: 'Not found' });
  res.json(photo);
});

// Withdraw consent for a photo
router.post('/:id/withdraw-consent', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const photo = await Photo.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    { 'consent.withdrawn': true, 'consent.withdrawnAt': new Date() },
    { new: true }
  );
  if (!photo) return res.status(404).json({ error: 'Not found' });
  res.json(photo);
});

// Delete photo
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const photo = await Photo.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!photo) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const photoRoutes = router;
