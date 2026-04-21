/**
 * Fix double-encoded settings values in database
 * Run: node scripts/fix-settings-encoding.mjs
 */
import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

const settings = await prisma.setting.findMany();
let fixed = 0;

for (const s of settings) {
  const raw = s.value;
  
  // If value is a string, it's double-encoded - fix it
  if (typeof raw === 'string') {
    try {
      let parsed = JSON.parse(raw);
      // If still a string after first parse, parse again
      if (typeof parsed === 'string') {
        parsed = JSON.parse(parsed);
      }
      // Update with proper object
      await prisma.setting.update({
        where: { id: s.id },
        data: { value: parsed }
      });
      console.log(`✅ Fixed: ${s.key}`);
      fixed++;
    } catch (e) {
      console.log(`⚠️  Skipped: ${s.key} (${e.message})`);
    }
  } else {
    console.log(`✓  OK: ${s.key} (already object)`);
  }
}

console.log(`\nDone. Fixed ${fixed} settings.`);
await prisma.$disconnect();
