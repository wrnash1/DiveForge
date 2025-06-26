import express from 'express';
import { Form } from '../database/schema/form';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List forms for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const forms = await Form.find({ tenantId });
  res.json(forms);
});

// Get form by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const form = await Form.findOne({ _id: req.params.id, tenantId });
  if (!form) return res.status(404).json({ error: 'Not found' });
  res.json(form);
});

// Create form
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const form = await Form.create({ ...req.body, tenantId });
    res.status(201).json(form);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update form
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const form = await Form.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!form) return res.status(404).json({ error: 'Not found' });
  res.json(form);
});

// Delete form
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const form = await Form.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!form) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const formRoutes = router;
