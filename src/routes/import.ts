import express from 'express';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// Import data (stub)
router.post('/', authenticateJWT, async (req, res) => {
  // In production, process import file/data (type, mapping, etc.)
  res.json({ success: true, message: 'Import processed (stub)' });
});

// Import status (stub)
router.get('/status/:importId', authenticateJWT, async (req, res) => {
  // In production, return import status/progress
  res.json({ success: true, importId: req.params.importId, status: 'completed' });
});

export const importRoutes = router;
