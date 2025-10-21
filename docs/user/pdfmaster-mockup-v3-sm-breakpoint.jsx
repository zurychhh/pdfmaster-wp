import React, { useState } from 'react';
import { Upload, Lock, Zap, DollarSign, Check, Star, FileText, Shield, Clock, CreditCard } from 'lucide-react';

export default function PDFMasterMockup() {
  const [isDragging, setIsDragging] = useState(false);

  return (
    <div className="min-h-screen bg-white">
      {/* Navigation */}
      <nav className="border-b border-gray-200 bg-white sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <FileText className="h-8 w-8 text-blue-600" />
              <span className="ml-2 text-xl font-bold text-gray-900">PDFMaster</span>
            </div>
            <div className="flex items-center space-x-8">
              <a href="#tools" className="text-gray-600 hover:text-gray-900">Tools</a>
              <a href="#pricing" className="text-gray-600 hover:text-gray-900">Pricing</a>
              <a href="#faq" className="text-gray-600 hover:text-gray-900">FAQ</a>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="bg-gradient-to-b from-blue-50 to-white py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-3xl mx-auto">
            <h1 className="text-5xl font-bold text-gray-900 mb-6">
              Professional PDF Tools in 30 Seconds
            </h1>
            <p className="text-xl text-gray-600 mb-8">
              Compress, merge, split and convert PDF files without installing software. Just $0.99 per action. No subscriptions, no hidden fees.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center mb-8">
              <button className="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition">
                Try Any Tool â€“ $0.99
              </button>
              <button className="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold text-lg hover:border-gray-400 transition">
                See How It Works
              </button>
            </div>
            <div className="flex flex-wrap justify-center gap-6 text-sm text-gray-600">
              <div className="flex items-center">
                <Check className="h-5 w-5 text-green-600 mr-2" />
                No signup required
              </div>
              <div className="flex items-center">
                <Check className="h-5 w-5 text-green-600 mr-2" />
                Files deleted after 1 hour
              </div>
              <div className="flex items-center">
                <Check className="h-5 w-5 text-green-600 mr-2" />
                Bank-level encryption
              </div>
              <div className="flex items-center">
                <Check className="h-5 w-5 text-green-600 mr-2" />
                2M+ users monthly
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Tool Interface Demo */}
      <section className="py-16 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div 
            className={`border-4 border-dashed rounded-xl p-12 text-center transition ${
              isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50'
            }`}
            onDragOver={(e) => { e.preventDefault(); setIsDragging(true); }}
            onDragLeave={() => setIsDragging(false)}
            onDrop={(e) => { e.preventDefault(); setIsDragging(false); }}
          >
            <Upload className="h-16 w-16 text-gray-400 mx-auto mb-4" />
            <h3 className="text-2xl font-semibold text-gray-900 mb-2">
              Drop your PDF here or click to browse
            </h3>
            <p className="text-gray-600 mb-4">
              Supported formats: PDF, DOCX, JPG, PNG â€¢ Max size: 50MB
            </p>
            <button className="bg-white border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
              Select File from Computer
            </button>
          </div>
          <div className="mt-6 text-center">
            <div className="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-lg">
              <Lock className="h-5 w-5 mr-2" />
              <span className="font-medium">Each action costs $0.99 â€¢ Files automatically deleted after 1 hour</span>
            </div>
          </div>
        </div>
      </section>

      {/* Tools Grid */}
      <section id="tools" className="py-20 bg-gray-50">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              All Tools, One Simple Price
            </h2>
            <p className="text-xl text-gray-600">
              $0.99 per action. No subscriptions, no packages, no complexity.
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <FileText className="h-6 w-6 text-blue-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-2">Compress PDF</h3>
              <p className="text-gray-600 mb-4">
                Reduce file size by up to 90% without quality loss. Perfect for email attachments.
              </p>
              <div className="text-2xl font-bold text-blue-600 mb-2">$0.99</div>
              <p className="text-sm text-gray-500">~8 seconds processing</p>
            </div>
            
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <FileText className="h-6 w-6 text-green-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-2">Merge PDFs</h3>
              <p className="text-gray-600 mb-4">
                Combine multiple PDF files into one organized document. Up to 20 files at once.
              </p>
              <div className="text-2xl font-bold text-blue-600 mb-2">$0.99</div>
              <p className="text-sm text-gray-500">~5 seconds processing</p>
            </div>
            
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <FileText className="h-6 w-6 text-purple-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-2">Split PDF</h3>
              <p className="text-gray-600 mb-4">
                Extract specific pages or split into separate files. Simple page range selection.
              </p>
              <div className="text-2xl font-bold text-blue-600 mb-2">$0.99</div>
              <p className="text-sm text-gray-500">~6 seconds processing</p>
            </div>
            
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-orange-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <FileText className="h-6 w-6 text-orange-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-2">Convert to PDF</h3>
              <p className="text-gray-600 mb-4">
                Convert Word, Excel, PowerPoint and images to PDF. Quality options available.
              </p>
              <div className="text-2xl font-bold text-blue-600 mb-2">$0.99</div>
              <p className="text-sm text-gray-500">~10 seconds processing</p>
            </div>
          </div>
        </div>
      </section>

      {/* Value Proposition */}
      <section className="py-20 bg-white">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Why Pay Monthly for Tools You Rarely Use?
            </h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <DollarSign className="h-6 w-6 text-blue-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">
                Pay Only When You Need It
              </h3>
              <p className="text-gray-600">
                No monthly subscriptions. Use once a month? Pay once. Use 10 times? Pay 10 times. Simple.
              </p>
            </div>
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <Check className="h-6 w-6 text-green-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">
                Zero Commitment
              </h3>
              <p className="text-gray-600">
                No account creation. No credit card on file. Just upload, pay once via Stripe, download, and done.
              </p>
            </div>
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <Shield className="h-6 w-6 text-purple-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">
                Military-Grade Security
              </h3>
              <p className="text-gray-600">
                Files encrypted during upload, processing, and download. Permanently deleted after 1 hour. No storage, no tracking.
              </p>
            </div>
            <div className="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition">
              <div className="bg-orange-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <Zap className="h-6 w-6 text-orange-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">
                Lightning-Fast Processing
              </h3>
              <p className="text-gray-600">
                Most operations complete in under 10 seconds. No waiting rooms, no throttlingâ€”instant results.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* How It Works */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Three Simple Steps
            </h2>
          </div>
          <div className="grid md:grid-cols-3 gap-12">
            <div className="text-center">
              <div className="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                1
              </div>
              <h3 className="text-2xl font-semibold text-gray-900 mb-3">Upload Your File</h3>
              <p className="text-gray-600 text-lg">
                Drag & drop your PDF, Word doc, or image. Up to 50MB per file.
              </p>
            </div>
            <div className="text-center">
              <div className="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                2
              </div>
              <h3 className="text-2xl font-semibold text-gray-900 mb-3">Pay $0.99</h3>
              <p className="text-gray-600 text-lg">
                One-time payment via Stripe. No subscription, no auto-renewal, no surprises.
              </p>
            </div>
            <div className="text-center">
              <div className="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                3
              </div>
              <h3 className="text-2xl font-semibold text-gray-900 mb-3">Download Instantly</h3>
              <p className="text-gray-600 text-lg">
                Get your processed file immediately. That's itâ€”you're done!
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Pricing Section */}
      <section id="pricing" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Simple, Honest Pricing
            </h2>
            <p className="text-xl text-gray-600">
              One price for everything. No tiers, no packages, no confusion.
            </p>
          </div>
          
          <div className="max-w-2xl mx-auto">
            <div className="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 border-2 border-blue-200">
              <div className="text-center">
                <div className="text-6xl font-bold text-gray-900 mb-2">$0.99</div>
                <div className="text-xl text-gray-600 mb-6">per action</div>
                <ul className="space-y-3 text-left max-w-md mx-auto mb-8">
                  <li className="flex items-start">
                    <Check className="h-6 w-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" />
                    <span className="text-gray-700">Any tool: Compress, Merge, Split, Convert</span>
                  </li>
                  <li className="flex items-start">
                    <Check className="h-6 w-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" />
                    <span className="text-gray-700">Files up to 50MB</span>
                  </li>
                  <li className="flex items-start">
                    <Check className="h-6 w-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" />
                    <span className="text-gray-700">No signup required</span>
                  </li>
                  <li className="flex items-start">
                    <Check className="h-6 w-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" />
                    <span className="text-gray-700">Secure processing with auto-delete</span>
                  </li>
                  <li className="flex items-start">
                    <Check className="h-6 w-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" />
                    <span className="text-gray-700">No subscription, no recurring charges</span>
                  </li>
                </ul>
                <button className="bg-blue-600 text-white px-10 py-4 rounded-lg font-bold text-lg hover:bg-blue-700 transition w-full">
                  Try Any Tool Now
                </button>
              </div>
            </div>
          </div>

          <div className="mt-12 bg-blue-50 border-2 border-blue-200 rounded-xl p-8 max-w-4xl mx-auto">
            <h3 className="text-2xl font-bold text-gray-900 mb-6 text-center">The Subscription Trap vs. The Smart Choice</h3>
            <div className="grid md:grid-cols-2 gap-8">
              <div className="bg-white rounded-lg p-6">
                <div className="flex items-start mb-4">
                  <span className="text-3xl mr-3">âŒ</span>
                  <div>
                    <p className="font-bold text-lg text-gray-900 mb-2">Typical Competitor (Smallpdf Pro)</p>
                    <p className="text-gray-600">$108/year subscription</p>
                    <p className="text-gray-600">Most users need only 9 actions/year</p>
                    <p className="text-red-600 font-semibold mt-2">= $99.09 wasted annually</p>
                  </div>
                </div>
              </div>
              <div className="bg-white rounded-lg p-6 border-2 border-green-500">
                <div className="flex items-start mb-4">
                  <span className="text-3xl mr-3">âœ…</span>
                  <div>
                    <p className="font-bold text-lg text-gray-900 mb-2">PDFMaster</p>
                    <p className="text-gray-600">Pay only when you use it</p>
                    <p className="text-gray-600">9 actions Ã— $0.99 each</p>
                    <p className="text-green-600 font-semibold mt-2">= $8.91 total per year</p>
                  </div>
                </div>
              </div>
            </div>
            <p className="mt-6 text-center text-xl font-bold text-blue-900">
              Save $99.09 annually by paying only for what you actually use.
            </p>
          </div>
        </div>
      </section>

      {/* Trust & Security */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Your Privacy Is Non-Negotiable
            </h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-12">
            <div className="text-center bg-white p-8 rounded-xl shadow-sm">
              <CreditCard className="h-12 w-12 text-blue-600 mx-auto mb-4" />
              <h3 className="text-xl font-semibold text-gray-900 mb-2">Secure Payments</h3>
              <p className="text-gray-600">
                We never see your card details. All payments processed by Stripe (PCI DSS Level 1 certified).
              </p>
            </div>
            <div className="text-center bg-white p-8 rounded-xl shadow-sm">
              <Shield className="h-12 w-12 text-blue-600 mx-auto mb-4" />
              <h3 className="text-xl font-semibold text-gray-900 mb-2">Data Protection</h3>
              <p className="text-gray-600">
                256-bit AES encryption during transfer. Files auto-deleted after 60 minutesâ€”no exceptions, no logs.
              </p>
            </div>
            <div className="text-center bg-white p-8 rounded-xl shadow-sm">
              <Lock className="h-12 w-12 text-blue-600 mx-auto mb-4" />
              <h3 className="text-xl font-semibold text-gray-900 mb-2">No Tracking, No Ads</h3>
              <p className="text-gray-600">
                We don't sell your data. We don't track your usage. You pay, you convert, you leave. Simple.
              </p>
            </div>
          </div>
          <div className="bg-yellow-50 border border-yellow-200 rounded-xl p-6 max-w-3xl mx-auto">
            <div className="flex items-start">
              <div className="flex-shrink-0">
                <div className="flex">
                  {[...Array(5)].map((_, i) => (
                    <Star key={i} className="h-5 w-5 text-yellow-500 fill-current" />
                  ))}
                </div>
              </div>
              <div className="ml-4">
                <p className="text-lg font-semibold text-gray-900 mb-2">4.9/5 from 2M+ users worldwide</p>
                <p className="text-gray-700 italic">"Finally, a PDF tool that respects my wallet AND my privacy. I've used it 3 times this year and paid less than $3 total."</p>
                <p className="text-gray-600 mt-1">â€” Sarah Thompson, Freelance Designer</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* FAQ */}
      <section id="faq" className="py-20 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Frequently Asked Questions
            </h2>
          </div>
          <div className="space-y-6">
            {[
              {
                q: "Why $0.99 per use instead of a subscription?",
                a: "Because most people process PDFs only 2-5 times per monthâ€”not 50. Why pay $10-20/month for something you barely use? With us, you pay $0.99 only when you need it. If you use it 10 times a year, that's $9.90 total instead of $120-240 for annual subscriptions."
              },
              {
                q: "Do I need to create an account?",
                a: "Nope. Just upload your file, pay, and download. We'll email you a receipt, but that's it. No passwords, no login, no profile."
              },
              {
                q: "What payment methods do you accept?",
                a: "We accept all major credit/debit cards via Stripe, plus PayPal and Google Pay."
              },
              {
                q: "Can I get a refund?",
                a: "Yes. If your file fails to process or the output is corrupted, email us within 24 hours for a full refundâ€”no questions asked."
              },
              {
                q: "How long do you keep my files?",
                a: "Maximum 1 hour. After that, they're permanently deleted from our servers. We don't store, share, or access your documents."
              },
              {
                q: "Is there a file size limit?",
                a: "Each file can be up to 50MB. Need larger? Email us for custom enterprise pricing."
              },
              {
                q: "What if I need to process 50+ files per month?",
                a: "For high-volume users (50+ actions/month), we offer volume discounts. Contact us for custom pricingâ€”still cheaper than subscriptions!"
              }
            ].map((faq, idx) => (
              <div key={idx} className="bg-gray-50 rounded-lg p-6 shadow-sm hover:shadow-md transition">
                <h3 className="text-lg font-semibold text-gray-900 mb-2">{faq.q}</h3>
                <p className="text-gray-600">{faq.a}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Final CTA */}
      <section className="py-20 bg-gradient-to-br from-blue-600 to-blue-800 text-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-4xl font-bold mb-4">
            Ready to Stop Wasting Money on Subscriptions?
          </h2>
          <p className="text-xl mb-8 text-blue-100">
            Join 2M+ smart users who pay only for what they use. Process your first PDF in under 60 seconds.
          </p>
          <button className="bg-white text-blue-600 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition mb-6">
            Try Any Tool â€“ $0.99
          </button>
          <div className="flex items-center justify-center text-blue-100">
            <Lock className="h-5 w-5 mr-2" />
            <span>Money-Back Guarantee â€¢ No Auto-Renewal â€¢ No Subscription Ever</span>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-gray-400 py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-4 gap-8">
            <div>
              <div className="flex items-center mb-4">
                <FileText className="h-6 w-6 text-blue-500" />
                <span className="ml-2 text-lg font-bold text-white">PDFMaster</span>
              </div>
              <p className="text-sm">Pay-per-use PDF tools. No subscriptions, no hassle, no wasted money.</p>
            </div>
            <div>
              <h4 className="text-white font-semibold mb-4">Tools</h4>
              <ul className="space-y-2 text-sm">
                <li><a href="#" className="hover:text-white">Compress PDF</a></li>
                <li><a href="#" className="hover:text-white">Merge PDFs</a></li>
                <li><a href="#" className="hover:text-white">Split PDF</a></li>
                <li><a href="#" className="hover:text-white">Convert to PDF</a></li>
              </ul>
            </div>
            <div>
              <h4 className="text-white font-semibold mb-4">Company</h4>
              <ul className="space-y-2 text-sm">
                <li><a href="#" className="hover:text-white">About Us</a></li>
                <li><a href="#" className="hover:text-white">Privacy Policy</a></li>
                <li><a href="#" className="hover:text-white">Terms of Service</a></li>
                <li><a href="#" className="hover:text-white">Contact</a></li>
              </ul>
            </div>
            <div>
              <h4 className="text-white font-semibold mb-4">Support</h4>
              <ul className="space-y-2 text-sm">
                <li><a href="#" className="hover:text-white">Help Center</a></li>
                <li><a href="#" className="hover:text-white">Refund Policy</a></li>
                <li><a href="#" className="hover:text-white">Volume Pricing</a></li>
              </ul>
            </div>
          </div>
          <div className="border-t border-gray-800 mt-8 pt-8 text-sm text-center">
            Â© 2025 PDFMaster. All rights reserved.
          </div>
        </div>
      </footer>
    </div>
  );
}