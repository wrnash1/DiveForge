import express from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import { User } from '../database/schema/user';

const router = express.Router();
const JWT_SECRET = process.env.JWT_SECRET || 'changeme';

// Register new user (admin only in production)
router.post('/register', async (req, res) => {
  const { tenantId, email, password, name, roles } = req.body;
  const passwordHash = await bcrypt.hash(password, 10);
  try {
    const user = await User.create({ tenantId, email, passwordHash, name, roles });
    res.status(201).json({ id: user._id, email: user.email });
  } catch (err) {
    res.status(400).json({ error: 'Registration failed' });
  }
});

// Login
router.post('/login', async (req, res) => {
  const { email, password } = req.body;
  const user = await User.findOne({ email, isActive: true });
  if (!user) return res.status(401).json({ error: 'Invalid credentials' });
  const valid = await bcrypt.compare(password, user.passwordHash);
  if (!valid) return res.status(401).json({ error: 'Invalid credentials' });
  const token = jwt.sign(
    { id: user._id, tenantId: user.tenantId, roles: user.roles, name: user.name },
    JWT_SECRET,
    { expiresIn: '8h' }
  );
  res.json({ token });
});

export const authRoutes = router;
