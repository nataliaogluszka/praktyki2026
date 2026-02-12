@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <form method="post" class="p-4 shadow rounded">
                    <h3 class="mb-3">Contact Us</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" placeholder="Your Name *" name="nameC" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="email" class="form-control" placeholder="Your Email *" name="emailC"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="phone" class="form-control" placeholder="Your Phone Number" name="phoneC">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-group mb-3">
                                <textarea name="message" class="form-control" placeholder="Your Message *" style="width: 100%; height: 144px;" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="form-group">
                                <input type="submit" name="submitBtn" class="btn btn-primary" value="Send Message">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
@endsection
