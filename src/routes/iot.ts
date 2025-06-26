import express from 'express';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// Health check for IoT integration
router.get('/health', (req, res) => {
  res.json({ status: 'ok', version: '1.0.0' });
});

// Example: Receive equipment telemetry (stub)
router.post('/equipment/:equipmentId/telemetry', authenticateJWT, (req, res) => {
  // Save telemetry data for equipment (not implemented)
  res.json({ success: true, message: 'Telemetry received (stub)' });
});

// Example: Get latest telemetry for equipment (stub)
router.get('/equipment/:equipmentId/telemetry', authenticateJWT, (req, res) => {
  // Return latest telemetry data (not implemented)
  res.json({ equipmentId: req.params.equipmentId, telemetry: {} });
});

export const iotRoutes = router;
