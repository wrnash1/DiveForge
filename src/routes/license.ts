import express from 'express';
import { License } from '../database/schema/license';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List licenses for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const licenses = await License.find({ tenantId });
  res.json(licenses);
});

// Get license by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const license = await License.findOne({ _id: req.params.id, tenantId });
  if (!license) return res.status(404).json({ error: 'Not found' });
  res.json(license);
});

// Create license
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const license = await License.create({ ...req.body, tenantId });
    res.status(201).json(license);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update license
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const license = await License.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!license) return res.status(404).json({ error: 'Not found' });
  res.json(license);
});

// Delete license
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const license = await License.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!license) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const licenseRoutes = router;
