import express from 'express';
import { B2BAccount } from '../database/schema/b2bAccount';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List B2B accounts for tenant
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const accounts = await B2BAccount.find({ tenantId });
  res.json(accounts);
});

// Get B2B account by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const account = await B2BAccount.findOne({ _id: req.params.id, tenantId });
  if (!account) return res.status(404).json({ error: 'Not found' });
  res.json(account);
});

// Create B2B account
router.post('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  try {
    const account = await B2BAccount.create({ ...req.body, tenantId });
    res.status(201).json(account);
  } catch (err) {
    res.status(400).json({ error: 'Create failed' });
  }
});

// Update B2B account
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const account = await B2BAccount.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!account) return res.status(404).json({ error: 'Not found' });
  res.json(account);
});

// Delete B2B account
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const account = await B2BAccount.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!account) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const b2bAccountRoutes = router;
