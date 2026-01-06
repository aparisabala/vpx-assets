import React from 'react'
import { createRoot } from 'react-dom/client';
export default function TestComponent() {
  return (
    <div>TestComponent</div>
  )
}
if (document.getElementById('TestComponent')) {
    createRoot(document.getElementById('TestComponent')).render(<TestComponent />)
}
