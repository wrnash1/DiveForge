import express from 'express';
import { Organization } from '../database/schema/organization';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List organizations (optionally filter by parentOrgId)
router.get('/', authenticateJWT, async (req, res) => {
  const { parentOrgId } = req.query;
  const query: any = {};
  if (parentOrgId) query.parentOrgId = parentOrgId;
  const orgs = await Organization.find(query);
  res.json(orgs);
});

// Get organization by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const org = await Organization.findById(req.params.id);
  if (!org) return res.status(404).json({ error: 'Not found' });
  res.json(org);
});

// Create organization
router.post('/', authenticateJWT, async (req, res) => {
  try {
    const org = await Organization.create(req.body);
    res.status(201).json(org);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update organization
router.put('/:id', authenticateJWT, async (req, res) => {
  const org = await Organization.findByIdAndUpdate(req.params.id, req.body, { new: true });
  if (!org) return res.status(404).json({ error: 'Not found' });
  res.json(org);
});

// Delete organization
router.delete('/:id', authenticateJWT, async (req, res) => {
  const org = await Organization.findByIdAndDelete(req.params.id);
  if (!org) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const organizationRoutes = router;
