import { Schema, model } from 'mongoose';

export interface ITheme {
  tenantId?: string; // null for global/theme marketplace
  name: string;
  description?: string;
  version: string;
  author: string;
  enabled: boolean;
  config?: Record<string, any>;
  installedAt?: Date;
  updatedAt: Date;
  createdAt: Date;
}

const ThemeSchema = new Schema<ITheme>({
  tenantId: { type: String, index: true },
  name: { type: String, required: true },
  description: String,
  version: { type: String, required: true },
  author: { type: String, required: true },
  enabled: { type: Boolean, default: false },
  config: Schema.Types.Mixed,
  installedAt: Date
}, {
  timestamps: true
});

export const Theme = model<ITheme>('Theme', ThemeSchema);
