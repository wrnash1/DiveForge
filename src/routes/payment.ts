import express from 'express';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List payments for tenant (stub)
router.get('/', authenticateJWT, async (req, res) => {
  // In production, fetch payments for tenant
  res.json([]);
});

// Create payment (stub)
router.post('/', authenticateJWT, async (req, res) => {
  // In production, process payment and return transaction details
  res.status(201).json({ success: true, payment: req.body });
});

// Refund payment (stub)
router.post('/:id/refund', authenticateJWT, async (req, res) => {
  // In production, process refund for payment ID
  res.json({ success: true, refunded: req.params.id });
});

export const paymentRoutes = router;
