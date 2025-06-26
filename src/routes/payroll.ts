import express from 'express';
import { Payroll } from '../database/schema/payroll';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List payroll records for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const payrolls = await Payroll.find({ tenantId });
  res.json(payrolls);
});

// Get payroll record by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const payroll = await Payroll.findOne({ _id: req.params.id, tenantId });
  if (!payroll) return res.status(404).json({ error: 'Not found' });
  res.json(payroll);
});

// Create payroll record
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const payroll = await Payroll.create({ ...req.body, tenantId });
    res.status(201).json(payroll);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update payroll record
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const payroll = await Payroll.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!payroll) return res.status(404).json({ error: 'Not found' });
  res.json(payroll);
});

// Delete payroll record
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const payroll = await Payroll.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!payroll) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const payrollRoutes = router;
