import express from 'express';
import { Course } from '../database/schema/course';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List courses for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const courses = await Course.find({ tenantId });
  res.json(courses);
});

// Get course by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const course = await Course.findOne({ _id: req.params.id, tenantId });
  if (!course) return res.status(404).json({ error: 'Not found' });
  res.json(course);
});

// Create course
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const course = await Course.create({ ...req.body, tenantId });
    res.status(201).json(course);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update course
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const course = await Course.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!course) return res.status(404).json({ error: 'Not found' });
  res.json(course);
});

// Delete course (soft delete not required here, but can be added)
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const course = await Course.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!course) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const courseRoutes = router;
