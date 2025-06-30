import express from 'express';
import { FormSubmission } from '../database/schema/formSubmission';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List submissions for a form
router.get('/form/:formId', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const submissions = await FormSubmission.find({ tenantId, formId: req.params.formId });
  res.json(submissions);
});

// Get submission by ID
router.get('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const submission = await FormSubmission.findOne({ _id: req.params.id, tenantId });
  if (!submission) return res.status(404).json({ error: 'Not found' });
  res.json(submission);
});

// Submit a form
router.post('/form/:formId', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const submittedBy = (req as any).user.id;
  try {
    const submission = await FormSubmission.create({
      tenantId,
      formId: req.params.formId,
      submittedBy,
      data: req.body.data,
      submittedAt: new Date(),
      status: 'submitted'
    });
    res.status(201).json(submission);
  } catch (err) {
    res.status(400).json({ error: 'Submit failed' });
  }
});

// Update submission status
router.put('/:id', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const submission = await FormSubmission.findOneAndUpdate(
    { _id: req.params.id, tenantId },
    req.body,
    { new: true }
  );
  if (!submission) return res.status(404).json({ error: 'Not found' });
  res.json(submission);
});

export const formSubmissionRoutes = router;
