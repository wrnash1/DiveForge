import express from 'express';
import { Announcement } from '../database/schema/announcement';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List announcements (global + tenant)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const now = new Date();
  const announcements = await Announcement.find({
    $and: [
      { $or: [{ tenantId }, { tenantId: null }] },
      { isActive: true },
      { startDate: { $lte: now } },
      { $or: [{ endDate: null }, { endDate: { $gte: now } }] }
    ]
  }).sort({ startDate: -1 });
  res.json(announcements);
});

// Get announcement by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const announcement = await Announcement.findById(req.params.id);
  if (!announcement) return res.status(404).json({ error: 'Not found' });
  res.json(announcement);
});

// Create announcement
router.post('/', authenticateJWT, async (req, res) => {
  const createdBy = (req as any).user.id;
  try {
    const announcement = await Announcement.create({ ...req.body, createdBy });
    res.status(201).json(announcement);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update announcement
router.put('/:id', authenticateJWT, async (req, res) => {
  const announcement = await Announcement.findByIdAndUpdate(req.params.id, req.body, { new: true });
  if (!announcement) return res.status(404).json({ error: 'Not found' });
  res.json(announcement);
});

// Delete announcement
router.delete('/:id', authenticateJWT, async (req, res) => {
  const announcement = await Announcement.findByIdAndDelete(req.params.id);
  if (!announcement) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const announcementRoutes = router;
