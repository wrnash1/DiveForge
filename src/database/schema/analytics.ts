import { Schema, model } from 'mongoose';

export interface IAnalytics {
  tenantId: string;
  type: string; // e.g. 'kpi', 'trend', 'custom'
  name: string;
  parameters: Record<string, any>;
  data: any;
  generatedAt: Date;
  createdBy: string;
  createdAt: Date;
  updatedAt: Date;
}

const AnalyticsSchema = new Schema<IAnalytics>({
  tenantId: { type: String, required: true, index: true },
  type: { type: String, required: true },
  name: { type: String, required: true },
  parameters: { type: Schema.Types.Mixed },
  data: { type: Schema.Types.Mixed },
  generatedAt: { type: Date, required: true },
  createdBy: { type: String, required: true }
}, {
  timestamps: true
});

export const Analytics = model<IAnalytics>('Analytics', AnalyticsSchema);
