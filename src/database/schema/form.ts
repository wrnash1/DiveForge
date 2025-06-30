import { Schema, model } from 'mongoose';

export interface IForm {
  tenantId: string;
  name: string;
  description?: string;
  fields: Array<{
    label: string;
    type: string; // e.g. text, number, date, select, signature, etc.
    required: boolean;
    options?: string[];
    order: number;
    validation?: Record<string, any>;
  }>;
  version: number;
  isActive: boolean;
  createdAt: Date;
  updatedAt: Date;
}

const FormSchema = new Schema<IForm>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  description: String,
  fields: [{
    label: { type: String, required: true },
    type: { type: String, required: true },
    required: { type: Boolean, default: false },
    options: [String],
    order: { type: Number, required: true },
    validation: Schema.Types.Mixed
  }],
  version: { type: Number, default: 1 },
  isActive: { type: Boolean, default: true }
}, {
  timestamps: true
});

export const Form = model<IForm>('Form', FormSchema);
