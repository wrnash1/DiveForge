import express from 'express';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List alerts for user (stub)
router.get('/', authenticateJWT, async (req, res) => {
  // In production, fetch alerts for user/tenant
  res.json([
    { timestamp: new Date(), type: 'info', message: 'Welcome to DiveForge!', read: false }
  ]);
});

// Mark alert as read (stub)
router.post('/:id/read', authenticateJWT, async (req, res) => {
  // In production, mark alert as read for user
  res.json({ success: true, alertId: req.params.id });
});

// Delete alert (stub)
router.delete('/:id', authenticateJWT, async (req, res) => {
  // In production, delete alert for user
  res.json({ success: true, alertId: req.params.id });
});

export const alertRoutes = router;
