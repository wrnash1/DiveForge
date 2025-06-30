import { Schema, model } from 'mongoose';

export interface IReport {
  tenantId: string;
  type: string; // e.g. 'financial', 'equipment', 'course', 'customer'
  name: string;
  parameters: Record<string, any>;
  generatedAt: Date;
  data: any;
  createdBy: string;
  createdAt: Date;
  updatedAt: Date;
}

const ReportSchema = new Schema<IReport>({
  tenantId: { type: String, required: true, index: true },
  type: { type: String, required: true },
  name: { type: String, required: true },
  parameters: { type: Schema.Types.Mixed },
  generatedAt: { type: Date, required: true },
  data: { type: Schema.Types.Mixed },
  createdBy: { type: String, required: true }
}, {
  timestamps: true
});

export const Report = model<IReport>('Report', ReportSchema);
