import express from 'express';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// Inbound webhook endpoint (public, for integrations)
router.post('/inbound/:source', async (req, res) => {
  // Handle inbound webhook from external service (stub)
  // You may want to verify signature, log event, etc.
  res.json({ success: true, source: req.params.source, received: true });
});

// Outbound webhook test (authenticated)
router.post('/outbound/test', authenticateJWT, async (req, res) => {
  // Simulate sending a webhook to an external service (stub)
  res.json({ success: true, message: 'Outbound webhook sent (stub)' });
});

export const webhookRoutes = router;
