import { Schema, model } from 'mongoose';

export interface IForum {
  tenantId: string;
  siteId?: string; // For site-specific forums
  title: string;
  description?: string;
  createdBy: string;
  posts: Array<{
    userId: string;
    message: string;
    createdAt: Date;
    updatedAt?: Date;
    attachments?: string[];
  }>;
  isLocked: boolean;
  createdAt: Date;
  updatedAt: Date;
}

const ForumSchema = new Schema<IForum>({
  tenantId: { type: String, required: true, index: true },
  siteId: String,
  title: { type: String, required: true },
  description: String,
  createdBy: { type: String, required: true },
  posts: [{
    userId: { type: String, required: true },
    message: { type: String, required: true },
    createdAt: { type: Date, required: true },
    updatedAt: Date,
    attachments: [String]
  }],
  isLocked: { type: Boolean, default: false }
}, {
  timestamps: true
});

export const Forum = model<IForum>('Forum', ForumSchema);
