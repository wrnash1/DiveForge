import express from 'express';
import { Theme } from '../database/schema/theme';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List themes (optionally filter by tenant/global)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const themes = await Theme.find({ $or: [{ tenantId }, { tenantId: null }] });
  res.json(themes);
});

// Get theme by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const theme = await Theme.findById(req.params.id);
  if (!theme) return res.status(404).json({ error: 'Not found' });
  res.json(theme);
});

// Install/enable theme
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const theme = await Theme.create({ ...req.body, tenantId, enabled: true, installedAt: new Date() });
    res.status(201).json(theme);
  } catch (err) {
    res.status(400).json({ error: 'Install failed' });
  }
});

// Update theme config
router.put('/:id', authenticateJWT, async (req, res) => {
  const theme = await Theme.findByIdAndUpdate(req.params.id, req.body, { new: true });
  if (!theme) return res.status(404).json({ error: 'Not found' });
  res.json(theme);
});

// Disable/uninstall theme
router.delete('/:id', authenticateJWT, async (req, res) => {
  const theme = await Theme.findByIdAndDelete(req.params.id);
  if (!theme) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const themeRoutes = router;
