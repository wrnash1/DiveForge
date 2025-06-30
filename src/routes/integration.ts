import express from 'express';
import { Integration } from '../database/schema/integration';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List integrations for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const integrations = await Integration.find({ tenantId });
  res.json(integrations);
});

// Get integration by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const integration = await Integration.findOne({ _id: req.params.id, tenantId });
  if (!integration) return res.status(404).json({ error: 'Not found' });
  res.json(integration);
});

// Create integration
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const integration = await Integration.create({ ...req.body, tenantId });
    res.status(201).json(integration);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update integration
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const integration = await Integration.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!integration) return res.status(404).json({ error: 'Not found' });
  res.json(integration);
});

// Delete integration
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const integration = await Integration.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!integration) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const integrationRoutes = router;
