import express from 'express';
import { Incident } from '../database/schema/incident';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List incidents for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const incidents = await Incident.find({ tenantId });
  res.json(incidents);
});

// Get incident by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const incident = await Incident.findOne({ _id: req.params.id, tenantId });
  if (!incident) return res.status(404).json({ error: 'Not found' });
  res.json(incident);
});

// Create incident
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const incident = await Incident.create({ ...req.body, tenantId });
    res.status(201).json(incident);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update incident
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const incident = await Incident.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!incident) return res.status(404).json({ error: 'Not found' });
  res.json(incident);
});

// Delete incident
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const incident = await Incident.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!incident) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const incidentRoutes = router;
