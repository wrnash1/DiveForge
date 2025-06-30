import express from 'express';
import { Report } from '../database/schema/report';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List reports for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const reports = await Report.find({ tenantId });
  res.json(reports);
});

// Get report by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const report = await Report.findOne({ _id: req.params.id, tenantId });
  if (!report) return res.status(404).json({ error: 'Not found' });
  res.json(report);
});

// Generate a new report (stub: just saves parameters and empty data)
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const userId = (req as any).user.id;
  const { type, name, parameters } = req.body;
  try {
    const report = await Report.create({
      tenantId,
      type,
      name,
      parameters,
      generatedAt: new Date(),
      data: {}, // In production, generate actual report data here
      createdBy: userId
    });
    res.status(201).json(report);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

export const reportRoutes = router;
