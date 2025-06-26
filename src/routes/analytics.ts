import express from 'express';
import { Analytics } from '../database/schema/analytics';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List analytics for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const analytics = await Analytics.find({ tenantId });
  res.json(analytics);
});

// Get analytics by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const analytics = await Analytics.findOne({ _id: req.params.id, tenantId });
  if (!analytics) return res.status(404).json({ error: 'Not found' });
  res.json(analytics);
});

// Generate analytics (stub: just saves parameters and empty data)
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const userId = (req as any).user.id;
  const { type, name, parameters } = req.body;
  try {
    const analytics = await Analytics.create({
      tenantId,
      type,
      name,
      parameters,
      data: {}, // In production, generate actual analytics data here
      generatedAt: new Date(),
      createdBy: userId
    });
    res.status(201).json(analytics);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

export const analyticsRoutes = router;
