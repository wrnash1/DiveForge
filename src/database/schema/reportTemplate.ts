import { Schema, model } from 'mongoose';

export interface IReportTemplate {
  tenantId?: string; // null for global templates
  name: string;
  description?: string;
  type: string; // e.g. 'financial', 'equipment', 'course', etc.
  fields: Array<{
    key: string;
    label: string;
    type: string;
    options?: string[];
    required?: boolean;
    order?: number;
  }>;
  layout?: Record<string, any>;
  isActive: boolean;
  createdBy: string;
  createdAt: Date;
  updatedAt: Date;
}

const ReportTemplateSchema = new Schema<IReportTemplate>({
  tenantId: { type: String, index: true },
  name: { type: String, required: true },
  description: String,
  type: { type: String, required: true },
  fields: [{
    key: { type: String, required: true },
    label: { type: String, required: true },
    type: { type: String, required: true },
    options: [String],
    required: Boolean,
    order: Number
  }],
  layout: Schema.Types.Mixed,
  isActive: { type: Boolean, default: true },
  createdBy: { type: String, required: true }
}, {
  timestamps: true
});

export const ReportTemplate = model<IReportTemplate>('ReportTemplate', ReportTemplateSchema);
