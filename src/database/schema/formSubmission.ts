import { Schema, model } from 'mongoose';

export interface IFormSubmission {
  tenantId: string;
  formId: string;
  submittedBy: string;
  data: Record<string, any>;
  submittedAt: Date;
  status: 'submitted' | 'reviewed' | 'archived';
  createdAt: Date;
  updatedAt: Date;
}

const FormSubmissionSchema = new Schema<IFormSubmission>({
  tenantId: { type: String, required: true, index: true },
  formId: { type: String, required: true },
  submittedBy: { type: String, required: true },
  data: { type: Schema.Types.Mixed, required: true },
  submittedAt: { type: Date, required: true },
  status: { type: String, enum: ['submitted', 'reviewed', 'archived'], default: 'submitted' }
}, {
  timestamps: true
});

export const FormSubmission = model<IFormSubmission>('FormSubmission', FormSubmissionSchema);
