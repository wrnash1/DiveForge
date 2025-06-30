import express from 'express';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// Health check for mobile app
router.get('/health', (req, res) => {
  res.json({ status: 'ok', version: '1.0.0' });
});

// Example: Get user profile for mobile app
router.get('/profile', authenticateJWT, (req, res) => {
  res.json({ user: (req as any).user });
});

// Example: Push notification registration (stub)
router.post('/register-push', authenticateJWT, (req, res) => {
  // Save push token for user/device (not implemented)
  res.json({ success: true });
});

// Example: Sync endpoint (stub)
router.post('/sync', authenticateJWT, (req, res) => {
  // Handle mobile sync logic (not implemented)
  res.json({ success: true, message: 'Sync endpoint not implemented' });
});

export const mobileRoutes = router;
