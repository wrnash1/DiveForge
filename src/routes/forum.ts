import express from 'express';
import { Forum } from '../database/schema/forum';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List forums for tenant (optionally filter by site)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { siteId } = req.query;
  const query: any = { tenantId };
  if (siteId) query.siteId = siteId;
  const forums = await Forum.find(query).sort({ createdAt: -1 });
  res.json(forums);
});

// Get forum by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const forum = await Forum.findOne({ _id: req.params.id, tenantId });
  if (!forum) return res.status(404).json({ error: 'Not found' });
  res.json(forum);
});

// Create forum
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const createdBy = (req as any).user.id;
  try {
    const forum = await Forum.create({ ...req.body, tenantId, createdBy, posts: [] });
    res.status(201).json(forum);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Add post to forum
router.post('/:id/posts', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const userId = (req as any).user.id;
  const { message, attachments } = req.body;
  const forum = await Forum.findOneAndUpdate(
    { _id: req.params.id, tenantId, isLocked: false },
    { $push: { posts: { userId, message, createdAt: new Date(), attachments } } },
    { new: true }
  );
  if (!forum) return res.status(404).json({ error: 'Forum not found or locked' });
  res.json(forum);
});

// Lock/unlock forum
router.post('/:id/lock', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const forum = await Forum.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    { isLocked: true },
    { new: true }
  );
  if (!forum) return res.status(404).json({ error: 'Not found' });
  res.json(forum);
});
router.post('/:id/unlock', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const forum = await Forum.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    { isLocked: false },
    { new: true }
  );
  if (!forum) return res.status(404).json({ error: 'Not found' });
  res.json(forum);
});

// Delete forum
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const forum = await Forum.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!forum) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const forumRoutes = router;
