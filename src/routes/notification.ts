import express from 'express';
import { Notification } from '../database/schema/notification';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List notifications for user or tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const userId = (req as any).user.id;
  const notifications = await Notification.find({
    tenantId,
    $or: [{ userId }, { userId: null }]
  }).sort({ createdAt: -1 });
  res.json(notifications);
});

// Mark notification as read
router.post('/:id/read', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const userId = (req as any).user.id;
  const notification = await Notification.findOneAndUpdate(
    { _id: req.params.id, tenantId, $or: [{ userId }, { userId: null }] },
    { read: true },
    { new: true }
  );
  if (!notification) return res.status(404).json({ error: 'Not found' });
  res.json(notification);
});

// Create notification (system or user)
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const notification = await Notification.create({ ...req.body, tenantId });
    res.status(201).json(notification);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Delete notification
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const userId = (req as any).user.id;
  const notification = await Notification.findOneAndDelete({
    _id: req.params.id,
    tenantId,
    $or: [{ userId }, { userId: null }]
  });
  if (!notification) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const notificationRoutes = router;
