import express from 'express';
import { ReportTemplate } from '../database/schema/reportTemplate';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List report templates (global + tenant)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const templates = await ReportTemplate.find({
    $or: [{ tenantId }, { tenantId: null }],
    isActive: true
  }).sort({ name: 1 });
  res.json(templates);
});

// Get report template by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const template = await ReportTemplate.findById(req.params.id);
  if (!template) return res.status(404).json({ error: 'Not found' });
  res.json(template);
});

// Create report template
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const createdBy = (req as any).user.id;
  try {
    const template = await ReportTemplate.create({ ...req.body, tenantId, createdBy });
    res.status(201).json(template);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update report template
router.put('/:id', authenticateJWT, async (req, res) => {
  const template = await ReportTemplate.findByIdAndUpdate(req.params.id, req.body, { new: true });
  if (!template) return res.status(404).json({ error: 'Not found' });
  res.json(template);
});

// Delete report template
router.delete('/:id', authenticateJWT, async (req, res) => {
  const template = await ReportTemplate.findByIdAndDelete(req.params.id);
  if (!template) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const reportTemplateRoutes = router;
