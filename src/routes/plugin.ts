import express from 'express';
import { Plugin } from '../database/schema/plugin';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List plugins (optionally filter by tenant/global)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const plugins = await Plugin.find({ $or: [{ tenantId }, { tenantId: null }] });
  res.json(plugins);
});

// Get plugin by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const plugin = await Plugin.findById(req.params.id);
  if (!plugin) return res.status(404).json({ error: 'Not found' });
  res.json(plugin);
});

// Install/enable plugin
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const plugin = await Plugin.create({ ...req.body, tenantId, enabled: true, installedAt: new Date() });
    res.status(201).json(plugin);
  } catch (err) {
    res.status(400).json({ error: 'Install failed' });
  }
});

// Update plugin config
router.put('/:id', authenticateJWT, async (req, res) => {
  const plugin = await Plugin.findByIdAndUpdate(req.params.id, req.body, { new: true });
  if (!plugin) return res.status(404).json({ error: 'Not found' });
  res.json(plugin);
});

// Disable/uninstall plugin
router.delete('/:id', authenticateJWT, async (req, res) => {
  const plugin = await Plugin.findByIdAndDelete(req.params.id);
  if (!plugin) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const pluginRoutes = router;
