import express from 'express';
import { Buddy } from '../database/schema/buddy';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List buddies for a customer
router.get('/customer/:customerId', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const buddies = await Buddy.find({
    tenantId,
    $or: [
      { customerId: req.params.customerId },
      { buddyId: req.params.customerId }
    ]
  });
  res.json(buddies);
});

// Send buddy request
router.post('/request', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { customerId, buddyId, notes } = req.body;
  try {
    const buddy = await Buddy.create({
      tenantId,
      customerId,
      buddyId,
      status: 'pending',
      requestedAt: new Date(),
      notes
    });
    res.status(201).json(buddy);
  } catch (err) {
    res.status(400).json({ error: 'Request failed' });
  }
});

// Accept buddy request
router.post('/:id/accept', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const buddy = await Buddy.findOneAndUpdate(
    { _id: req.params.id, tenantId, status: 'pending' },
    { status: 'accepted', acceptedAt: new Date() },
    { new: true }
  );
  if (!buddy) return res.status(404).json({ error: 'Not found or already accepted' });
  res.json(buddy);
});

// Reject buddy request
router.post('/:id/reject', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const buddy = await Buddy.findOneAndUpdate(
    { _id: req.params.id, tenantId, status: 'pending' },
    { status: 'rejected' },
    { new: true }
  );
  if (!buddy) return res.status(404).json({ error: 'Not found or already processed' });
  res.json(buddy);
});

// Remove buddy
router.delete('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const buddy = await Buddy.findOneAndDelete({ _id: req.params.id, tenantId });
  if (!buddy) return res.status(404).json({ error: 'Not found' });
  res.json({ success: true });
});

export const buddyRoutes = router;
